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
#import "ItemsViewController.h"
#import "ProcessAssetsDelegate.h"
#import "Dictionary.h"

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
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAllProcesses", @"action", nil];
	procVC.xmlItemName = [NSString stringWithString:@"BusinessProcess"];
	ProcessAssetsDelegate* delegate = [[[ProcessAssetsDelegate alloc] init] autorelease];
	delegate.navigationController = self.navigationController;
	procVC.delegate = delegate;
	procVC.title = NSLocalizedString(@"processesViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"BP_STATUS", @"Status", 
									  @"BP_RTO", @"Rto", 
									  @"BP_CRITICALITY", @"Criticality", 
									  @"BP_TYPE", @"Type", 
									  @"BP_PERIODICITY", @"Pariodicity", nil];

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


- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = NSLocalizedString(@"mainMenuFormTitle", nil);
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
