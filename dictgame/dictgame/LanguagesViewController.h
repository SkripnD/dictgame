//
//  LanguagesViewController.h
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <GameKit/GameKit.h>

@interface LanguagesViewController : UITableViewController
{
    __block NSArray * languages;
}

- (void) showDummyView;
- (void) hideDummyView;
@end
