//
//  DSLabel.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "DSLabel.h"

@implementation DSLabel

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

- (id) init
{
    if (self = [super init]) {
        drawBorder         = NO;
        borderPercentWidth = 0;
    }
    return self;
}

- (void) willMoveToSuperview:(UIView *)newSuperview
{
    self.font = [UIFont fontWithName:@"HelveticaNeue-Thin" size:18.0f];
}

- (void) setFontSize:(CGFloat)fontSize
{
    self.font = [UIFont fontWithName:@"HelveticaNeue-Thin" size:fontSize];
}

- (void) setBottomBorder:(CGFloat) _percentWidth
{
    borderPercentWidth = _percentWidth;
    drawBorder         = YES;
    [self setNeedsDisplay];
}

- (void)drawRect:(CGRect)rect
{
    if (drawBorder) {
        float borderSize = 0.5f;
        CGFloat width = self.frame.size.width * borderPercentWidth;
        UIColor * borderColor = [UIColor grayColor];
        
        CGContextRef context = UIGraphicsGetCurrentContext();
        CGContextClearRect(context, self.bounds);
        
        //draw the bottom border
        
        CGContextSetFillColorWithColor(context, borderColor.CGColor);
        CGContextFillRect(context, CGRectMake((self.frame.size.width-width)/2, self.frame.size.height - borderSize, width, borderSize));
        drawBorder = NO;
    }
    [super drawRect: rect];
}


@end
