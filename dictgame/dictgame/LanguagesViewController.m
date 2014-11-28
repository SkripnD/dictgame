//
//  LanguagesViewController.m
//  dictgame
//
//  Created by Denis Skripnichenko on 27/11/14.
//  Copyright (c) 2014 Denis Skripnichenko. All rights reserved.
//

#import "LanguagesViewController.h"
#import "DSSearchField.h"
#import "Preferences.h"
#import "Translate.h"
#import "Helpers.h"
#import "LanguageTableViewCell.h"
#import "GameViewController.h"
#import "ViewController+DummyImage.h"

#define headerViewHeight 40
#define dummyHeaderViewHeight 40

@interface LanguagesViewController ()

@end

@implementation LanguagesViewController


- (void)viewDidLoad {
    [super viewDidLoad];
    self.automaticallyAdjustsScrollViewInsets = NO;
   
    
    // Uncomment the following line to preserve selection between presentations.
    // self.clearsSelectionOnViewWillAppear = NO;
    
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    // self.navigationItem.rightBarButtonItem = self.editButtonItem;
}

- (void) viewWillAppear:(BOOL)animated
{
    
    [self initializeInterface];
    
    languages = (NSArray *)[[Preferences shared] getValue:@"languages"];
    if (!languages) {
        [self showDummyView];
        [[Translate shared] updateLanguagesOnComplete:^(){
            languages = (NSArray *)[[Preferences shared] getValue:@"languages"];
            [self hideDummyView: ^(){
                [self updateInterface];
            }];
        } onError:^(){
            [self hideDummyView];
        }];
    } else {
        
        [self updateInterface];
    }

}



- (void) initializeInterface
{
    self.automaticallyAdjustsScrollViewInsets = YES;
    self.title                                = @"Выберите язык";
  
    
}

- (void) updateInterface
{
    [self.tableView reloadData];
}

- (void) showDummyView
{
    
}

- (void) hideDummyView
{
    
    [self updateInterface];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

#pragma mark - Languages table view data

- (NSInteger) numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger) tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [languages count];
}

#pragma mark - Languages table view delegate

- (UITableViewCell *) tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString * cellIdentifier = @"LanguageCell";
    
    LanguageTableViewCell *cell = [self.tableView dequeueReusableCellWithIdentifier:cellIdentifier];
    
    if (cell == nil) {
        cell = [[LanguageTableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier];
    }
    
    NSDictionary * language = [languages objectAtIndex:indexPath.row];
    
    cell.textLabel.text = language[@"full"];
    //cell.imageView.image = [UIImage imageNamed:[thumbnails objectAtIndex:indexPath.row]];

    return cell;
}

- (CGFloat) tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
    return [languages count] > 0 ? 0 : dummyHeaderViewHeight;
}

- (UIView *) tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
    return [languages count] > 0 ? [self headerViewForLanguagesList] : [self headerViewForEmptyData];
}

- (UIView *) headerViewForEmptyData
{
    UIView * view = [[UIView alloc] initWithFrame: CGRectMake(0, 0, self.view.bounds.size.width, dummyHeaderViewHeight)];
    [view setBackgroundColor: [UIColor whiteColor]];
    
    UIActivityIndicatorView * indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle: UIActivityIndicatorViewStyleGray];
    [indicator setFrame: [Helpers centerFrame:indicator.frame inFrame:view.frame]];
    [indicator startAnimating];
    
    [view addSubview: indicator];
    return view;
}

- (UIView *) headerViewForLanguagesList
{
    return nil;
}

- (CGFloat) tableView:(UITableView *)tableView heightForFooterInSection:(NSInteger)section
{
    return 0.1;
}

- (void) tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
   
    UIStoryboard * storyboard             = [UIStoryboard storyboardWithName:@"Main" bundle:nil];
    GameViewController * gameController = [storyboard instantiateViewControllerWithIdentifier:@"GameViewController"];
    [gameController setLangInfo: [languages objectAtIndex: indexPath.row]];
    [self.navigationController pushViewController:gameController animated:YES];
}

@end
