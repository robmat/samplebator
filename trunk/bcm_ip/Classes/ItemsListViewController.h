//
//  ItemsListViewController.h
//  bcm_ip
//
//  Created by User on 3/19/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ItemsViewController.h"

@interface ItemsListViewController : UIViewController {

	IBOutlet UITableView* tableViewOutlet;
	IBOutlet UIToolbar* toolbarOutlet;
	ItemsViewController* itemsViewController;
}

@property (nonatomic, retain) ItemsViewController* itemsViewController;

@end
