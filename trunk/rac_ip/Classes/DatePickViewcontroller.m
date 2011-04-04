//
//  DatePickViewcontroller.m
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "DatePickViewcontroller.h"


@implementation DatePickViewcontroller

- (IBAction) pickAction: (id) sender {
	NSDate* date = datePicker.date;
	UILocalNotification *localNotif = [[UILocalNotification alloc] init];	
	localNotif.fireDate = date;
	localNotif.timeZone = [NSTimeZone defaultTimeZone];
	localNotif.alertBody = @"This is a sample offer, click to view details.";
	localNotif.alertAction = @"Notification.";
	localNotif.soundName = UILocalNotificationDefaultSoundName;
	localNotif.applicationIconBadgeNumber = 1;	
	[[UIApplication sharedApplication] scheduleLocalNotification:localNotif];
	[localNotif release];
	[self.navigationController popViewControllerAnimated:YES];
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
- (IBAction) backAction: (id) sender {
	[self.navigationController popViewControllerAnimated:YES];
}
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
	[datePicker release];
	[super dealloc];
}


@end
