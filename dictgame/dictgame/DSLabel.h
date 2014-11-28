//
//  DSLabel.h
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "DSTextField.h"

@interface DSLabel : UILabel
{
    DSTextFieldBorder _border;
    
    BOOL    drawBorder;
    CGFloat borderPercentWidth;
}

- (void) setFontSize: (CGFloat) fontSize;
- (void) setBottomBorder: (CGFloat) percentWidth;
@end
