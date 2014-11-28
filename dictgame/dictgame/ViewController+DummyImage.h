//
//  ViewController+DummyImage.h
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#ifndef dictgame_ViewController_DummyImage_h
#define dictgame_ViewController_DummyImage_h



@interface UIViewController (DummyImage)
- (void) showDummyView:(void(^)())afterShow;
- (void) hideDummyView:(void(^)())afterHide;
@end

@implementation UIViewController (DummyImage)
- (void) showDummyView:(void(^)())afterShow
{
    UIView * dummyView;
    if (afterShow) {
        afterShow();
    }
}

- (void) hideDummyView:(void(^)())afterHide
{
    
    if (afterHide) {
        afterHide();
    }
}
@end

#endif
