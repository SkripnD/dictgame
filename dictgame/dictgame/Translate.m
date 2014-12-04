//
//  Translate.m
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "Translate.h"
#import "AFNetworking.h"
#import "Preferences.h"

@implementation Translate

+ (Translate *) shared
{
    static Translate * sharedTranslate;
    
    @synchronized(self)
    {
        if (!sharedTranslate)
            sharedTranslate = [[Translate alloc] init];
        
        return sharedTranslate;
    }
}

// Get all language directions and languages titles
- (void) updateLanguagesOnComplete:(void(^)())onSuccess onError:(void(^)())onError
{
    NSString * rootUrl = [[Preferences shared] getYandexUrl];
    NSString * key     = [[Preferences shared] getYandexKey];
    NSString * url     = [NSString stringWithFormat:@"%@/getLangs?ui=ru&key=%@", rootUrl, key];
    AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
    [manager GET:url parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        [self parseDirs:responseObject[@"dirs"] withAbbs:responseObject[@"langs"]];
        onSuccess();
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        onError();
    }];
}

// Comprasion tranlate directions and language titles
- (void) parseDirs: (NSArray *) dirs withAbbs: (NSDictionary *) abbs
{
    directions = [[NSMutableArray alloc] init];
    NSString * dirRegexp  = @"^ru-[a-z]{2}$";
    NSPredicate * dirTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", dirRegexp];

    for (NSString * direction in dirs) {
        if ([dirTest evaluateWithObject: direction]) {
            NSString * abb  = [[direction componentsSeparatedByString:@"-"] objectAtIndex: 1];
            NSString * full = abbs[abb];
            [directions addObject: @{
                @"abb"  : abb,
                @"full" : full
            }];
        }
    }
    // Sort directions by language title alphabetically
    [directions sortUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"full" ascending:YES]]];
    [[Preferences shared] setValue:directions forKey:@"languages"];
}

- (NSArray *) getLanguages
{
    return directions;
}

- (void) getWordsWithTranslation:(NSString *)langAbb forType:(WordType)wordType count:(NSInteger)count onSuccess:(void(^)(NSArray * result))onSuccess
{
    NSString * rootUrl = wordServerAddr;
    NSString * url     = [NSString stringWithFormat:@"%@/%@?count=%ld", rootUrl, [self wordTypeToString: wordType], (long)count];
    AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
    [manager GET:url parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        [self translateArrayOfStrings:(NSArray *)responseObject langAbb:langAbb onSuccess:onSuccess];
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
#warning Add error handler
    }];
}

- (void) translateArrayOfStrings: (NSArray *) array langAbb:(NSString *) abb onSuccess:(void(^)(NSArray * result))onSuccess
{
    NSString * words      = [array componentsJoinedByString: @"."];
    NSString * rootUrl    = [[Preferences shared] getYandexUrl];
    NSString * key        = [[Preferences shared] getYandexKey];
    NSString * url        = [NSString stringWithFormat:@"%@/translate", rootUrl];
    NSDictionary * params = @{
                                 @"key"    : key,
                                 @"text"   : words,
                                 @"lang"   : [NSString stringWithFormat:@"ru-%@", abb],
                                 @"format" : @"plain"
                            };

    AFHTTPRequestOperationManager *manager = [AFHTTPRequestOperationManager manager];
    [manager GET:url parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSLog(@"%@", responseObject);
        NSArray * translated = [self parseTranslatedText:responseObject[@"text"][0]];
        NSMutableArray * result     = [[NSMutableArray alloc] init];
        for (int i = 0; i < [array count]; i++) {
            [result addObject:@{
                @"text": array[i],
                @"translated": translated[i]
            }];
        }
        onSuccess(result);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
#warning Add error handler
        
    }];
}

- (NSArray *) parseTranslatedText: (NSString *) string
{
    return [string componentsSeparatedByString:@"."];
}


- (NSString *) wordTypeToString:(WordType) type
{
    switch (type) {
        case WordTypeAdjective:
            return @"adjectives";
        case WordTypeAdverb:
            return @"adverbs";
        case WordTypeNoun:
            return @"nouns";
        case WordTypeVerb:
            return @"verbs";
        default:
            break;
    }
    return nil;
}
@end
