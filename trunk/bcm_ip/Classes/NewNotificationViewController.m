//
//  NewNotificationViewController.m
//  bcm_ip
//
//  Created by User on 3/23/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "NewNotificationViewController.h"
#import "NotificationGroupsView.h"
#import "HttpRequestWrapper.h"

@implementation NewNotificationViewController

@synthesize templateItem, addressesGroupIds, itemsViewController,
incidentName,
messageContent,
callOpt1,
callOpt2,
callOpt3,
callOpt4,
callOpt5,
isPersonalized,
requiresPin,
free1,
free2,
voiceIntro,
voice,
sms,
email;

- (void) addressGroupAction: (id) sender {
	NotificationGroupsView* ngvc = [[NotificationGroupsView alloc] initWithNibName:nil bundle:nil];
	ngvc.nnvc = self;
	[self.navigationController pushViewController:ngvc animated:YES];
	[ngvc release];
}
- (void) newNotificationAction: (id) sender {
	NSLog(@"sdvsd");
	HttpRequestWrapper* http = [[HttpRequestWrapper alloc] initWithDelegate:self];
	//group1=18&user=bcm&password=bcmtest&action=notify&isPinRequired=true&isEmailType=true&isVoiceType=true&isPersonalized=true&message=test&callOpt1=tak&isSmsType=true&isBbPin=true&isImType=true&voiceIntro=test
	
	NSDictionary* params = [NSDictionary dictionaryWithObjectsAndKeys:nil];
	
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
	itemsViewController.nnvc = self;
	[itemsViewController viewDidLoad];
}
- (void)updateTextLabelAtIndexPath:(NSIndexPath*)indexPath string:(NSString*)string {
	NSLog(@"See input: %@ from section: %d row: %d, should update models appropriately", string, indexPath.section, indexPath.row);
	if (indexPath.row == 0 && indexPath.section == 0) {
		self.incidentName = string;
	} 
	if (indexPath.row == 1 && indexPath.section == 0) {
		self.messageContent = string;
	}
	if (indexPath.row == 2 && indexPath.section == 0) {
		self.voiceIntro = string;
	}
	if (indexPath.row == 0 && indexPath.section == 1) {
		self.voice = string;
	}
	if (indexPath.row == 1 && indexPath.section == 1) {
		self.sms = string;
	}
	if (indexPath.row == 2 && indexPath.section == 1) {
		self.email = string;
	}
	if (indexPath.row == 0 && indexPath.section == 2) {
		self.callOpt1 = string;
	}
	if (indexPath.row == 1 && indexPath.section == 2) {
		self.callOpt2 = string;
	}
	if (indexPath.row == 2 && indexPath.section == 2) {
		self.callOpt3 = string;
	}
	if (indexPath.row == 3 && indexPath.section == 2) {
		self.callOpt4 = string;
	}
	if (indexPath.row == 4 && indexPath.section == 2) {
		self.callOpt5 = string;
	}
	if (indexPath.row == 0 && indexPath.section == 3) {
		self.isPersonalized = string;
	}
	if (indexPath.row == 1 && indexPath.section == 3) {
		self.requiresPin = string;
	}
	if (indexPath.row == 0 && indexPath.section == 4) {
		self.free1 = string;
	}
	if (indexPath.row == 1 && indexPath.section == 4) {
		self.free2 = string;
	}
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
	[incidentName release];
	[messageContent release];
	[callOpt1 release];
	[callOpt2 release];
	[callOpt3 release];
	[callOpt4 release];
	[callOpt5 release];
	[free1 release];
	[free2 release];
	[isPersonalized release];
	[requiresPin release];
	[voiceIntro release];
	[voice release];
	[sms release];
	[email release];	
    [super dealloc];
}


@end
