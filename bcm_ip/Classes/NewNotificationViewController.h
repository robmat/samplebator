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
}

@property (nonatomic, retain) NSDictionary* templateItem;

@end
