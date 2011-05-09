//
//  TableViewControllerWrapper.m
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "TableViewControllerWrapper.h"
#import "TableVieController.h"

@implementation TableViewControllerWrapper

@synthesize tableView, tableVC;

- (void)viewDidLoad {
    [super viewDidLoad];
	tableVC = [[TableViewController alloc] initWithStyle:UITableViewStylePlain];
	tableVC.tableView = tableView;
	[tableVC viewDidLoad];
	self.title = @"Table View";
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
	[tableVC release];
	[tableView dealloc];
    [super dealloc];
}

@end
