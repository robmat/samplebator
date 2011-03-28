//
//  NewNotificationViewController.m
//  bcm_ip
//
//  Created by User on 3/23/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NewNotificationViewController.h"
#import "NotificationGroupsView.h"

@implementation NewNotificationViewController

@synthesize templateItem, addressesGroupIds;

- (void) addressGroupAction: (id) sender {
	NotificationGroupsView* ngvc = [[NotificationGroupsView alloc] initWithNibName:nil bundle:nil];
	ngvc.nnvc = self;
	[self.navigationController pushViewController:ngvc animated:YES];
	[ngvc release];
}
- (void) newNotificationAction: (id) sender {

}
- (void) addGroupId: (NSString*) idStr {
	[addressesGroupIds addObject:idStr];
}
- (void) delGroupId: (NSString*) idStr {
	[addressesGroupIds removeObjectIdenticalTo:idStr];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = NSLocalizedString(@"newNotificationViewTitle", nil);
	addressesGroupIds = [[NSMutableArray alloc] init];
	itemsViewController = [[NewNotificationTableViewController alloc] init];
	itemsViewController.toolbarOutlet = toolbarOutlet;
	itemsViewController.tableViewOutlet = tableViewOutlet;
	itemsViewController.templateItem = templateItem;
	[itemsViewController viewDidLoad];
}


/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)viewDidUnload {
    [super viewDidUnload];
}


- (void)dealloc {
	[addressesGroupIds release];
	[tableViewOutlet release];
	[toolbarOutlet release];
	[itemsViewController release];
	[templateItem release];
    [super dealloc];
}


@end
