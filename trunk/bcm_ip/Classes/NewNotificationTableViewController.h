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
}

@property (nonatomic, retain) UITableView* tableViewOutlet;
@property (nonatomic, retain) UIToolbar* toolbarOutlet;

@end
