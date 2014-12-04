//
//  Preferences.m
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "Preferences.h"

@implementation Preferences


+ (Preferences *) shared
{
    static Preferences * sharedPreferences;
    
    @synchronized(self)
    {
        if (!sharedPreferences)
            sharedPreferences = [[Preferences alloc] init];
        
        return sharedPreferences;
    }
}

- (void) setValue:(id)value forKey:(NSString *)key
{
    NSString * path = [[NSBundle mainBundle] pathForResource:@"preferences" ofType:@"plist"];
    NSMutableDictionary * preferencesDict = nil;
    // Get current plist values as dictionary and rewrite it
    @synchronized(path) {
        preferencesDict = [[NSMutableDictionary alloc] initWithContentsOfFile:path];
        [preferencesDict setObject:value forKey:key];
        [preferencesDict writeToFile:path atomically:YES];
    }
}

- (id) getValue:(NSString *)key
{
    NSString * path = [[NSBundle mainBundle] pathForResource:@"preferences" ofType:@"plist"];
    NSDictionary * preferencesDict = nil;
    @synchronized(path) {
        preferencesDict = [[NSDictionary alloc] initWithContentsOfFile:path];
    }
    // All plist values to dictionary
    return [preferencesDict objectForKey:key];
}

- (NSString *) getYandexKey
{
    return (NSString *)[self getValue:@"yandex_key"];
}

- (NSString *) getYandexUrl
{
    return (NSString *)[self getValue:@"yandex_url"];
}

- (NSString *) getVkId
{
  return (NSString *)[self getValue:@"vk_id"];
}

- (NSString *) getVkKey
{
    return (NSString *)[self getValue:@"vk_key"];
}

- (void) setVkToken:(VKAccessToken *)token
{
    [self setValue:[token accessToken] forKey:@"vk_token"];
}

- (NSString *) getVkTokenString
{
    return [self getValue:@"vk_token"];
}

@end
