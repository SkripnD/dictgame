//
//  DummyView.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "DummyView.h"

@implementation DummyView

/*
// Only override drawRect: if you perform custom drawing.
// An empty implementation adversely affects performance during animation.
- (void)drawRect:(CGRect)rect {
    // Drawing code
}
*/

- (void) awakeFromNib
{
    [self initializeInterface];
}

- (id) initWithFrame:(CGRect)frame
{
    if (self = [super initWithFrame: frame]) {
        [self initializeInterface];
    }
    return self;
}

- (void) initializeInterface
{
    self.alpha           = 0.8;
    self.backgroundColor = [UIColor blackColor];
    
    [_indicator setHidesWhenStopped: YES];
    [_indicator startAnimating];
}

@end
