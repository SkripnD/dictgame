//
//  QuestionView.h
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "DSLabel.h"
#import "DSButton.h"

@class QuestionView;
@protocol QuestionViewDelgate <NSObject>
@required
- (void) answerComplete: (QuestionView *) view;
- (void) answerFail: (QuestionView *) view;

@end

typedef enum {
    QuestionStatusNone     = 0,
    QuestionStatusFail     = 1,
    QuestionStatusComplete = 2
} QuestionStatus;


@interface QuestionView : UIView
{
    BOOL active;
    NSArray * words;
    UIColor * failColor;
    UIColor * successColor;
    UIColor * normalColor;
    NSArray * answerButtons;
}
@property (nonatomic) QuestionStatus questionStatus;
@property (nonatomic) NSInteger number;
@property (strong, nonatomic) id<QuestionViewDelgate> delegate;
@property (strong, nonatomic) IBOutlet DSLabel  * titleLabel;
@property (strong, nonatomic) IBOutlet DSButton * firstButton;
@property (strong, nonatomic) IBOutlet DSButton * secondButton;
@property (strong, nonatomic) IBOutlet DSButton * thirdButton;

- (QuestionStatus) status;
- (void) setWords: (NSArray *) _words;
@end
