//
//  TableViewControllerWrapper.h
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "TableViewController.h"

@interface TableViewControllerWrapper : UIViewController {

	IBOutlet UITableView* tableView;
	TableViewController* tableVC;
	NSArray* dataArray;
}

@property (nonatomic, retain) UITableView* tableView;
@property (nonatomic, retain) TableViewController* tableVC;
@property (nonatomic, retain) NSArray* dataArray;

@end
