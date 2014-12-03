//
//  Helpers.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "Helpers.h"

@implementation Helpers



+(CGRect) centerFrame:(CGRect)frame inFrame:(CGRect)inFrame
{
    CGRect centeringFrame = CGRectMake(inFrame.size.width/2 - frame.size.width/2, inFrame.size.height/2 - frame.size.height/2, frame.size.width, frame.size.height);
    return  centeringFrame;
}

+(CGRect) changeY: (CGFloat) y forFrame:(CGRect) frame
{
    return CGRectMake(frame.origin.x, y, frame.size.width, frame.size.height);
}
@end
