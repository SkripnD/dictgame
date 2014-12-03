//
//  Helpers.h
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

@interface NSMutableArray (Shuffling)
- (void)shuffle;
@end

@implementation NSMutableArray (Shuffling)

- (void)shuffle
{
    NSUInteger count = [self count];
    for (NSUInteger i = 0; i < count; ++i) {
        NSInteger remainingCount = count - i;
        NSInteger exchangeIndex = i + arc4random_uniform((u_int32_t )remainingCount);
        [self exchangeObjectAtIndex:i withObjectAtIndex:exchangeIndex];
    }
}

@end

@interface Helpers : NSObject
+ (CGRect) changeY: (CGFloat) y forFrame:(CGRect) frame;
+ (CGRect)centerFrame: (CGRect) frame inFrame: (CGRect) inframe;

@end
