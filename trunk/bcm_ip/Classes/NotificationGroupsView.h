//
//  NotificationGroupsView.h
//  bcm_ip
//
//  Created by User on 3/28/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "NotificationGroupsTableViewController.h"
#import "NewNotificationViewController.h"

@interface NotificationGroupsView : UIViewController {
	IBOutlet UITableView* tableViewOutlet;
	IBOutlet UIToolbar* toolbarOutlet;
	NotificationGroupsTableViewController* itemsViewController;
	NewNotificationViewController* nnvc;
}

@property (nonatomic, retain) NewNotificationViewController* nnvc;

@end
