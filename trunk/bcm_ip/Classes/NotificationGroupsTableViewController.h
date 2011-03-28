//
//  NotificationGroupsTableViewController.h
//  bcm_ip
//
//  Created by User on 3/28/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HttpRequestWrapper.h"

@interface NotificationGroupsTableViewController : UITableViewController {
	UITableView* tableViewOutlet;
	UIToolbar* toolbarOutlet;
	HttpRequestWrapper* httpRequest;
	NSMutableArray* itemsArray;
	BOOL anyItemsAvailable;
}
@property (nonatomic, retain) UITableView* tableViewOutlet;
@property (nonatomic, retain) UIToolbar* toolbarOutlet;

@end
