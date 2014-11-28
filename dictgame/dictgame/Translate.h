//
//  Translate.h
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <Foundation/Foundation.h>

#define wordServerAddr @"http://dict.dev"

typedef enum {
    WordTypeVerb = 0,
    WordTypeAdverb,
    WordTypeNoun,
    WordTypeAdjective
} WordType;

@interface Translate : NSObject
{
    NSMutableArray * directions;
}

+ (Translate *) shared;

- (NSArray *) getLanguages;
- (void) updateLanguagesOnComplete:(void(^)())onSuccess onError:(void(^)())onError;
- (void) getWordsWithTranslation:(NSString *)langAbb forType:(WordType)wordType count:(NSInteger)count onSuccess:(void(^)(NSArray * result))onSuccess;
@end
