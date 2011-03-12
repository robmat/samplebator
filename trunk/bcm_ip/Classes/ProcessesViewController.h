//
//  ProcessesViewController.h
//  bcm_ip
//
//  Created by User on 3/10/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HttpRequestWrapper.h"

@interface ProcessesViewController : UITableViewController {
	HttpRequestWrapper* httpRequest;
	NSMutableArray* itemsArray;
	NSNumber* selectedRow;
}
- (UITableViewCell*) composeViewForSelectedRow: (NSIndexPath*) indexPath cellContentFrame: (CGRect) frame;
@end
