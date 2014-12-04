//
//  DSButton.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "DSButton.h"

@implementation DSButton

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

- (void) willMoveToSuperview:(UIView *)newSuperview
{
    self.titleLabel.adjustsFontSizeToFitWidth = NO;
    self.titleLabel.font = [UIFont fontWithName:@"HelveticaNeue" size:18.0f];
    [super willMoveToSuperview: newSuperview];

}

- (void) setFontSize:(CGFloat)fontSize
{
    self.titleLabel.font = [UIFont fontWithName:@"HelveticaNeue" size:fontSize];
}

@end
