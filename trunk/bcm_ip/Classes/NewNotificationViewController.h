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
}
@property (nonatomic, retain) NSMutableArray* addressesGroupIds;
@property (nonatomic, retain) NSDictionary* templateItem;

- (void) addressGroupAction: (id) sender;
- (void) newNotificationAction: (id) sender;
- (void) addGroupId: (NSString*) idStr;
- (void) delGroupId: (NSString*) idStr;
@end
