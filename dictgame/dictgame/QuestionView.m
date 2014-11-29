//
//  QuestionView.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "QuestionView.h"
#import "Helpers.h"



@implementation QuestionView
@synthesize delegate = _delegate;
@synthesize questionStatus;
@synthesize number;


- (void) willMoveToSuperview:(UIView *)newSuperview
{
    [_titleLabel setFrame: CGRectMake(0, _titleLabel.frame.origin.y, self.frame.size.width, _titleLabel.frame.size.height)];
    [_firstButton setFrame: CGRectMake(0, _firstButton.frame.origin.y, self.frame.size.width, _firstButton.frame.size.height)];
    [_secondButton setFrame: CGRectMake(0, _secondButton.frame.origin.y, self.frame.size.width, _secondButton.frame.size.height)];
    [_thirdButton setFrame: CGRectMake(0, _thirdButton.frame.origin.y, self.frame.size.width, _thirdButton.frame.size.height)];
}

- (void) setWords: (NSArray *) _words
{
    words = _words;
    [self initializeQuestion];
}

- (void) initializeQuestion
{
    active = YES;
    questionStatus = QuestionStatusNone;
    
    successColor = [UIColor greenColor];
    failColor    = [UIColor redColor];
    normalColor  = [UIColor blackColor];
    
    [_titleLabel setText:[words firstObject][@"text"]];
    [_titleLabel setBottomBorder: 0.7];
    [_titleLabel setFontSize: 28];
    
    

    answerButtons = [NSArray arrayWithObjects:_firstButton, _secondButton, _thirdButton, nil];
    NSMutableArray * shuffleWords = [NSMutableArray arrayWithArray: words];
    [shuffleWords shuffle];
    
    for (int i = 0; i < [shuffleWords count]; i++) {
        DSButton * btn = (DSButton *)[answerButtons objectAtIndex: i];
        [btn setTitle:(NSString *)shuffleWords[i][@"translated"] forState:UIControlStateNormal];
        [btn setTintColor: normalColor];
        [btn setFontSize: 24];
        [btn addTarget:self action:@selector(answerBtnClick:) forControlEvents:UIControlEventTouchUpInside];
    }
    
}

- (QuestionStatus) status
{
    return questionStatus;
}

- (void) lockQuestion
{
    active = NO;
}

- (void) answerBtnClick: (id) sender
{
    if (active) {
        NSDictionary * rigthWord = [words firstObject];
        DSButton * btn = sender;
        if (btn.titleLabel.text == rigthWord[@"translated"]) {
            [self answerComplete: btn];
        } else {
            [self answerFail: btn];
        }
        [self lockQuestion];
    }
    
}

- (void) answerComplete: (DSButton *) btn
{
    [btn setTintColor: successColor];
    self.questionStatus = QuestionStatusComplete;
    if (_delegate && [_delegate respondsToSelector:@selector(answerComplete:)]) {
        [_delegate answerComplete: self];
    }
}

- (void) answerFail: (DSButton *) btn
{
    [btn setTintColor: failColor];
    self.questionStatus = QuestionStatusFail;
    if (_delegate && [_delegate respondsToSelector:@selector(answerFail:)]) {
        [_delegate answerFail: self];
    }
}

@end
