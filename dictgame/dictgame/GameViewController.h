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
#import "VKSdk.h"


@interface GameViewController : UIViewController <UIScrollViewDelegate, QuestionViewDelgate, VKSdkDelegate>
{
    UIScrollView   * _scroll_questions;
    NSMutableArray * questionsViews;
    NSArray        * words;
    NSInteger rightAnswersCount;
    NSInteger failAnswersCount;
}

@property (strong, nonatomic) NSDictionary * langInfo;

@property (strong, nonatomic) IBOutlet UIView *resultView;
@property (strong, nonatomic) IBOutlet DSLabel *resultLabel;
@property (strong, nonatomic) IBOutlet DSButton *repeatButton;
@property (strong, nonatomic) IBOutlet DSButton *changeLanguageButton;
@property (strong, nonatomic) IBOutlet DSButton *shareButton;

@property (strong, nonatomic) IBOutlet UIView   * welcomeView;
@property (strong, nonatomic) IBOutlet DSLabel  * welcomeLabel;
@property (strong, nonatomic) IBOutlet DSButton * startButton;
@property (strong, nonatomic) IBOutlet DSButton * cancelButton;
@property (strong, nonatomic) IBOutlet UIActivityIndicatorView * startIndicator;
- (IBAction)startButtonClick:(id)sender;
- (IBAction)cancelButtonClick:(id)sender;
- (IBAction)repeatButtonClick:(id)sender;
- (IBAction)changeLanguageButtonClick:(id)sender;
- (IBAction)shareButtonClick:(id)sender;

@end
