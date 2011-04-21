//
//  NewNotificationViewController.h
//  bcm_ip
//
//  Created by User on 3/23/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "NewNotificationTableViewController.h"

@interface NewNotificationViewController : UIViewController {

	IBOutlet UITableView* tableViewOutlet;
	IBOutlet UIToolbar* toolbarOutlet;
	NewNotificationTableViewController* itemsViewController;
	NSDictionary* templateItem;
	NSMutableArray* addressesGroupIds;
	
	
	NSString* incidentName;
	NSString* messageContent;
	NSString* callOpt1;
	NSString* callOpt2;
	NSString* callOpt3;
	NSString* callOpt4;
	NSString* callOpt5;
	NSString* isPersonalized;
	NSString* requiresPin;
	NSString* free1;
	NSString* free2;
	NSString* voiceIntro;
	NSString* voice;
	NSString* sms;
	NSString* email;
}

@property (nonatomic, retain) NSMutableArray* addressesGroupIds;
@property (nonatomic, retain) NSDictionary* templateItem;
@property (nonatomic, retain) NewNotificationTableViewController* itemsViewController;
@property (nonatomic, retain) NSString* incidentName;
@property (nonatomic, retain) NSString* messageContent;
@property (nonatomic, retain) NSString* callOpt1;
@property (nonatomic, retain) NSString* callOpt2;
@property (nonatomic, retain) NSString* callOpt3;
@property (nonatomic, retain) NSString* callOpt4;
@property (nonatomic, retain) NSString* callOpt5;
@property (nonatomic, retain) NSString* isPersonalized;
@property (nonatomic, retain) NSString* requiresPin;
@property (nonatomic, retain) NSString* free1;
@property (nonatomic, retain) NSString* free2;
@property (nonatomic, retain) NSString* voiceIntro;
@property (nonatomic, retain) NSString* voice;
@property (nonatomic, retain) NSString* sms;
@property (nonatomic, retain) NSString* email;

- (void) addressGroupAction: (id) sender;
- (void) newNotificationAction: (id) sender;
- (void) addGroupId: (NSString*) idStr;
- (void) delGroupId: (NSString*) idStr;
- (void)updateTextLabelAtIndexPath:(NSIndexPath*)indexPath string:(NSString*)string;
@end
