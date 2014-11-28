//
//  DSTextField.m
//  WelcomeScreen
//
//  Created by Denis Skripnichenko on 14/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "DSTextField.h"



@implementation DSTextField
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
       
    }
    return self;
}

- (void) awakeFromNib
{
    self.layer.cornerRadius = 0;
    self.borderStyle        = UITextBorderStyleLine;
    _border                 = DSTextFieldBorderBottom;
    [self selfCreate];
}


- (void)drawRect:(CGRect)rect
{
    float borderSize = 0.5f;
    UIColor * borderColor = [UIColor grayColor];
    
    CGContextRef context = UIGraphicsGetCurrentContext();
    CGContextClearRect(context, self.bounds);
    
    //draw the bottom border
    if ((_border&DSBorderBottom)) {
        CGContextSetFillColorWithColor(context, borderColor.CGColor);
        CGContextFillRect(context, CGRectMake(0.0f, self.frame.size.height - borderSize, self.frame.size.width, borderSize));
    }
    
    //draw the top border
    if ((_border&DSBorderTop)) {
        CGContextSetFillColorWithColor(context, borderColor.CGColor);
        CGContextFillRect(context, CGRectMake(0.0f, borderSize, self.frame.size.width, borderSize));
    }
    
    //draw the left border
    if ((_border&DSBorderLeft)) {
        CGContextSetFillColorWithColor(context, borderColor.CGColor);
        CGContextFillRect(context, CGRectMake(0.0f, 0.0f, borderSize, self.frame.size.height));

    }
    
    //draw the right border
    if ((_border&DSBorderRight)) {
        CGContextSetFillColorWithColor(context, borderColor.CGColor);
        CGContextFillRect(context, CGRectMake(self.frame.size.width - borderSize, 0.0f, borderSize, self.frame.size.height));
    }
    
}

- (void) selfCreate
{
    self.font = [UIFont fontWithName:@"HelveticaNeue-Thin" size:18.0f];
    
    CGRect frameRect = self.frame;
    frameRect.size.height = 50;
    self.frame = frameRect;
}

- (void) setBorder:(DSTextFieldBorder)border
{
    _border = DSTextFieldBorderLeft;
   // [self setNeedsLayout];
}


@end
