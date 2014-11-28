//
//  GameViewController.h
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "DSLabel.h"
#import "DSButton.h"
#import "QuestionView.h"

@interface GameViewController : UIViewController <UIScrollViewDelegate, QuestionViewDelgate>
{
    UIScrollView   * _scroll_questions;
    NSMutableArray * questionsViews;
    NSArray        * words;
    NSInteger rightAnswersCount;
    NSInteger failAnswersCount;
}

@property (strong, nonatomic) NSDictionary * langInfo;

@property (strong, nonatomic) IBOutlet UIView   * welcomeView;
@property (strong, nonatomic) IBOutlet DSLabel  * welcomeLabel;
@property (strong, nonatomic) IBOutlet DSButton * startButton;
@property (strong, nonatomic) IBOutlet DSButton * cancelButton;
@property (strong, nonatomic) IBOutlet UIActivityIndicatorView * startIndicator;
- (IBAction)startButtonClick:(id)sender;
- (IBAction)cancelButtonClick:(id)sender;

@end
