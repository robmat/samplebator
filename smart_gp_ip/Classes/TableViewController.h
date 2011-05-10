//
//  TableViewController.h
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface TableViewController : UITableViewController {
	NSArray* dataArray;
	UINavigationController* navController;
}

@property (nonatomic, retain) NSArray* dataArray;
@property (nonatomic, retain) UINavigationController* navController;

@end
