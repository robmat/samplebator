//
//  ProcessesViewController.h
//  bcm_ip
//
//  Created by User on 3/10/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HttpRequestWrapper.h"
#import "ClickListener.h"
#import "Dictionary.h"

@interface ItemsViewController : UITableViewController {
	HttpRequestWrapper* httpRequest;
	NSMutableArray* itemsArray;
	int selectedRow;
	BOOL anyItemsAvailable;
	
	Dictionary* dictionary;
	NSDictionary* requestParams;
	NSString* xmlItemName;
	id <ClickListener> delegate;
}

@property (nonatomic, retain) NSDictionary* requestParams;
@property (nonatomic, retain) NSString* xmlItemName;
@property (nonatomic, retain) id <ClickListener> delegate;
@property (nonatomic, retain) Dictionary* dictionary;

- (UITableViewCell*) composeViewForSelectedRow: (NSIndexPath*) indexPath cellContentFrame: (CGRect) frame;
@end
