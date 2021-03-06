//
//  GameViewController.m
//  dictgame
//
//  Created by Denis Skripnichenko on 28/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "GameViewController.h"
#import "ViewController+DummyImage.h"
#import "Helpers.h"
#import "Translate.h"
#import "Preferences.h"

@interface GameViewController ()

@end

@implementation GameViewController
@synthesize langInfo = _langInfo;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    
    // Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) viewWillAppear:(BOOL)animated
{
    [self initializeWelcomeView];
}

- (void) initializeWelcomeView
{
    // Scroll view with questions
    _scroll_questions = [[UIScrollView alloc] initWithFrame: self.view.bounds];
    [_scroll_questions setAlpha: 0];
    [_scroll_questions setPagingEnabled: YES];
    [_scroll_questions setDelegate: self];
    [self.view addSubview: _scroll_questions];
    
    // First view with language title and start button
    [_welcomeView setFrame: self.view.bounds];
    [_welcomeLabel setFontSize: 34];
    [_welcomeLabel setText: _langInfo[@"full"]];
    

    [_startButton setFontSize:25];
    
    [_cancelButton setFontSize:25];
    [_cancelButton setAlpha: 0];
        
    
    [_resultView setFrame: self.view.bounds];
    [_resultLabel setFrame: CGRectMake(0, _resultLabel.frame.origin.y, self.view.frame.size.width, _resultLabel.frame.size.height)];
    [_resultLabel setFontSize: 25];
    [_resultView setAlpha:0];

    [self interfaceDummyMode];
    
    
    
    
}



- (void) startGameWithWords: (NSArray *) _words
{
    words = _words;
    rightAnswersCount = failAnswersCount = 0;
    [self changePageNumber: 1];
    [self.navigationItem.titleView setAlpha: 1];
    [self constructQuestionsView];
}

- (void) cancelGame
{
    [self interfaceDummyMode];
}

- (void) shareAchievementsVk
{
    [VKSdk initializeWithDelegate:self andAppId:[[Preferences shared] getVkId]];
    if ([VKSdk wakeUpSession]) {
            NSString * text = [NSString stringWithFormat:@"Вы ответили правильно на %ld вопросов из %lu и набрали %ld очков", (long)rightAnswersCount,[words count]/3, rightAnswersCount * 10];
            VKRequest * postReq = [[VKApi wall] post:@{VK_API_MESSAGE : text}];
            postReq.attempts = 10;
        [postReq executeWithResultBlock:^(VKResponse * response) {
        } errorBlock:^(NSError * error) {
#warning add error handler
            if (error.code != VK_API_ERROR) {
                [error.vkError.request repeat];
            }
            else {
                NSLog(@"VK error: %@", error);
            }
        }];
    } else {
        [VKSdk authorize:@[@"email,wall"]];
    }
}

- (void) interfaceGameModeAndStartGame
{
    [_startIndicator startAnimating];
    [_scroll_questions setContentOffset:CGPointMake(0, 0)];
    [UIView animateWithDuration:0.2 animations:^(){
        [_welcomeView setAlpha: 0];
        [_startButton setAlpha: 0];
        [_resultView  setAlpha: 0];
        [self.navigationItem setHidesBackButton: YES animated:NO];
    } completion:^(BOOL completed){
        WordType randomType = (WordType) (arc4random() % (int) WordTypeAdjective);
        // Get words for random part of speech
        [[Translate shared] getWordsWithTranslation:_langInfo[@"abb"] forType:randomType count:30 onSuccess:^(NSArray * result){
            [self startGameWithWords: result];
        }];
    }];
}

- (void) interfaceDummyMode
{
    [self.view bringSubviewToFront: _welcomeView];
    [UIView animateWithDuration:0.2 animations:^(){
        [_cancelButton setAlpha: 0];
        [_scroll_questions setAlpha: 0];
        [self.navigationItem setHidesBackButton:NO];
        [self.navigationItem.titleView setAlpha:0];
        [self.navigationItem setTitle:@""];
        [_welcomeView setAlpha: 1];
        [_startButton setAlpha: 1];
        [_startIndicator stopAnimating];
    } completion:^(BOOL completed){
    }];
    
}

- (void) interfaceResultMode
{
    [_resultLabel setText:[NSString stringWithFormat:@"Вы ответили правильно на %ld вопросов из %lu и набрали %ld очков", (long)rightAnswersCount,[words count]/3, rightAnswersCount * 10]];
    [self.view bringSubviewToFront: _resultView];
    [UIView animateWithDuration:0.2 animations:^(){
        [self.navigationItem.titleView setAlpha: 0];
        [_scroll_questions setAlpha: 0];
        [_welcomeView setAlpha: 0];
        [_cancelButton setAlpha: 0];
        [_resultView setAlpha: 1];
    } completion:^(BOOL completed){
    }];
}

- (void) constructQuestionsView
{
    questionsViews = [[NSMutableArray alloc] init];
    NSMutableArray * wordsForQuestionView = [[NSMutableArray alloc] init];
    NSInteger questionNumber = 1;
 
    for (int i = 1; i <= [words count]; i++) {
        [wordsForQuestionView addObject:words[i-1]];
        if (i%3 == 0) {
            QuestionView * view = [[[NSBundle mainBundle] loadNibNamed:@"QuestionView" owner:self options:nil] firstObject];
            [view setWords: wordsForQuestionView];
            [view setDelegate:self];
            view.number = questionNumber++;
            [questionsViews addObject:view];

            wordsForQuestionView = [[NSMutableArray alloc] init];
        }
    }
    
    CGFloat xOffset = 0;
    for (QuestionView * view in questionsViews) {
        [view setFrame: CGRectOffset(self.view.bounds, xOffset, 0)];
        xOffset += view.frame.size.width;
        [_scroll_questions addSubview: view];
    }
    
    [_scroll_questions setContentSize:CGSizeMake(_scroll_questions.frame.size.width * [questionsViews count], _scroll_questions.frame.size.height)];
    [self.view bringSubviewToFront: _scroll_questions];
    [self.view bringSubviewToFront: _cancelButton];
    [UIView animateWithDuration:0.2 delay:0.1 options:UIViewAnimationOptionCurveEaseInOut
                     animations:^(){
        [_cancelButton setAlpha: 1];
        [_scroll_questions setAlpha:1];
    } completion:^(BOOL finished) {
        
    }];
}

- (void) changePageNumber: (NSInteger) page
{
   self.title = [NSString stringWithFormat:@"%ld из %u", (long)page, [words count]/3];
}

- (void) testComplete
{
    [self interfaceResultMode];
}

- (void) notAnsweredQuestionCount
{
    
}

#pragma mark Button actions

- (IBAction)startButtonClick:(id)sender
{
    [self interfaceGameModeAndStartGame];
}

- (IBAction)cancelButtonClick:(id)sender
{
    [self cancelGame];
}

- (IBAction)repeatButtonClick:(id)sender
{
    [self interfaceGameModeAndStartGame];
}

- (IBAction)changeLanguageButtonClick:(id)sender
{
    [self.navigationController popViewControllerAnimated:YES];
}

- (IBAction)shareButtonClick:(id)sender
{
    [self shareAchievementsVk];
}

#pragma mark ScrollView methods

- (void) scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    float fractionalPage = scrollView.contentOffset.x / scrollView.frame.size.width;
     NSInteger pageNumber = lround(fractionalPage);
    [self changePageNumber: ++pageNumber];
}

- (void) moveToPage: (NSInteger) page
{
    [_scroll_questions setContentOffset:CGPointMake(_scroll_questions.frame.size.width * page, 0) animated:YES];
    [self changePageNumber: page + 1];
}

- (NSInteger) currentPage
{
    return lround(_scroll_questions.contentOffset.x / _scroll_questions.frame.size.width);
}

- (void) nextPage
{
    [self moveToPage: [self currentPage] + 1];
}

- (void) nextQuestion
{
    NSInteger nextQuestionNumber = -1;
    for (QuestionView * view in questionsViews) {
        if (view.status == QuestionStatusNone) {
            nextQuestionNumber = view.number;
            break;
        }
    }
    if (nextQuestionNumber == -1) {
        [self testComplete];
    } else {
        [self moveToPage: nextQuestionNumber - 1];
    }
}

#pragma mark QuestionsView delegate
- (void) answerFail: (QuestionView *) view
{
    failAnswersCount++;
    [self nextQuestion];
    
}

- (void) answerComplete: (QuestionView *) _view
{
    rightAnswersCount++;
    [self nextQuestion];
}

#pragma mark VkDelegate
-(void) vkSdkReceivedNewToken:(VKAccessToken*) newToken
{
    [[Preferences shared] setVkToken:newToken];
    [self shareAchievementsVk];
}

-(void) vkSdkUserDeniedAccess:(VKError*) authorizationError
{
    
}

- (void) vkSdkShouldPresentViewController:(UIViewController *)controller
{
    [self presentViewController:controller animated:YES completion:nil];
}

- (void) vkSdkNeedCaptchaEnter:(VKError *)captchaError
{
    
}

- (void) vkSdkTokenHasExpired:(VKAccessToken *)expiredToken
{
    
}

@end
