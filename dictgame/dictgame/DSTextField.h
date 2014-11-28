//
//  DSTextField.h
//  WelcomeScreen
//
//  Created by Denis Skripnichenko on 14/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <UIKit/UIKit.h>
#define DSBorderLeft   1
#define DSBorderRight  2
#define DSBorderBottom 4
#define DSBorderTop    8
typedef enum {
    DSTextFieldBorderLeft   = DSBorderLeft,
    DSTextFieldBorderRight  = DSBorderRight,
    DSTextFieldBorderBottom = DSBorderBottom,
    DSTextFieldBorderTop    = DSBorderTop
} DSTextFieldBorder;

@interface DSTextField : UITextField
{
    DSTextFieldBorder _border;
}

- (void) selfCreate;
- (void) setBorder: (DSTextFieldBorder) border;
@end
