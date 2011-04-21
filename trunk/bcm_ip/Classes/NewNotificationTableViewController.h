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
	id nnvc; //#import "NewNotificationViewController.h"
	
	NSString* incidentName;
	NSString* messageContent;
	NSString* callOpt1;
	NSString* callOpt2;
	NSString* callOpt3;
	NSString* callOpt4;
	NSString* callOpt5;
	BOOL isPersonalized;
	BOOL requiresPin;
	NSString* free1;
	NSString* free2;
	BOOL voiceIntro;
	BOOL voice;
	BOOL sms;
	BOOL email;
}

@property (nonatomic, retain) UITableView* tableViewOutlet;
@property (nonatomic, retain) UIToolbar* toolbarOutlet;
@property (nonatomic, retain) NSDictionary* templateItem;
@property (nonatomic, retain) id nnvc;
@property (nonatomic, retain) NSString* incidentName;
@property (nonatomic, retain) NSString* messageContent;
@property (nonatomic, retain) NSString* callOpt1;
@property (nonatomic, retain) NSString* callOpt2;
@property (nonatomic, retain) NSString* callOpt3;
@property (nonatomic, retain) NSString* callOpt4;
@property (nonatomic, retain) NSString* callOpt5;
@property (nonatomic, retain) NSString* free1;
@property (nonatomic, retain) NSString* free2; 

@end
