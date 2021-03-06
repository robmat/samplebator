//
//  NotificationGroupsView.m
//  bcm_ip
//
//  Created by User on 3/28/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NotificationGroupsView.h"
#import "NotificationGroupsTableViewController.h"

@implementation NotificationGroupsView

@synthesize nnvc;
/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/

- (void)viewDidLoad {
    [super viewDidLoad];
	[itemsViewController release];
	itemsViewController = [[NotificationGroupsTableViewController alloc] init];
	itemsViewController.toolbarOutlet = toolbarOutlet;
	itemsViewController.tableViewOutlet = tableViewOutlet;
	itemsViewController.nnvc = self.nnvc;
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
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}


@end
