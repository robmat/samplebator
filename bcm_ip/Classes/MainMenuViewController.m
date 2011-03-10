//
//  MainMenuViewController.m
//  bcm_ip
//
//  Created by User on 3/8/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "MainMenuViewController.h"
#import "bcm_ipAppDelegate.h"
#import "LoginViewController.h"
#import "ProcessesViewController.h"

@implementation MainMenuViewController

- (IBAction) logoutAction: (id) sender {
	[[NSFileManager defaultManager] removeItemAtPath:[bcm_ipAppDelegate getLoginDataFilePath] error:nil];
	LoginViewController* loginVC = [[LoginViewController alloc] init];
	[self.navigationController pushViewController:loginVC animated:YES];
	[loginVC release];
}
- (IBAction) supportAction: (id) sender {
	NSURL *url = [NSURL URLWithString:@"http://support.bcmlogic.com/"];
	[[UIApplication sharedApplication] openURL:url];
}
- (IBAction) processesAction: (id) sender {
	ProcessesViewController* procVC = [[ProcessesViewController alloc] init];
	[self.navigationController pushViewController:procVC animated:YES];
	[procVC release];
}	
/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/

/*
// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
}
*/

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
