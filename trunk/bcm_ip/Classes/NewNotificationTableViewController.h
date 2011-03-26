//
//  NewNotificationTableViewController.h
//  bcm_ip
//
//  Created by User on 3/23/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface NewNotificationTableViewController : UITableViewController {

	UITableView* tableViewOutlet;
	UIToolbar* toolbarOutlet;
	NSDictionary* templateItem;
	
	NSString* incidentName;
	NSString* messageContent;
	BOOL voiceIntro;
	BOOL voice;
	BOOL sms;
	BOOL email;
	NSString* callOpt1;
	NSString* callOpt2;
	NSString* callOpt3;
	NSString* callOpt4;
	NSString* callOpt5;
	BOOL isPersonalized;
	BOOL requiresPin;
	NSString* free1;
	NSString* free2;
}

@property (nonatomic, retain) UITableView* tableViewOutlet;
@property (nonatomic, retain) UIToolbar* toolbarOutlet;
@property (nonatomic, retain) NSDictionary* templateItem;

@end
