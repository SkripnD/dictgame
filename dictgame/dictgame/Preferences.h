//
//  Preferences.h
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "VKSdk.h"

@interface Preferences : NSObject

+ (Preferences *) shared;

- (id) getValue: (NSString *) key;
- (void) setValue: (id) value forKey: (NSString *) key;

- (void) setVkToken:(VKAccessToken *) token;
- (NSString *) getVkTokenString;

- (NSString *) getYandexKey;
- (NSString *) getYandexUrl;
- (NSString *) getVkId;
- (NSString *) getVkKey;
@end
