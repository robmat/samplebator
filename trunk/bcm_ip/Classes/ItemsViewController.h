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
#import "CacheManager.h"

@interface ActivateScenarioResultManager : NSObject {
	
}
- (void) requestFailed:(ASIHTTPRequest *)request;
- (void) activateScenarioWithResult: (NSString*) result;
- (void) requestFinished:(ASIHTTPRequest *)request;
@end

@interface ItemsViewController : UITableViewController {
	HttpRequestWrapper* httpRequest;
	NSMutableArray* itemsArray;
	int selectedRow;
	BOOL anyItemsAvailable;
	UIBarButtonItem *browserBtn;
	UITableView* tableViewOutlet;
	UIToolbar* toolbarOutlet;
	float frameWidth;
	CacheManager* cacheManager;
	
	UITableViewCellAccessoryType accessory;
	Dictionary* dictionary;
	NSDictionary* requestParams;
	NSString* xmlItemName;
	id <ClickListener> delegate;
	ActivateScenarioResultManager* actSceResMan;
}

@property (nonatomic, retain) NSDictionary* requestParams;
@property (nonatomic, retain) NSString* xmlItemName;
@property (nonatomic, retain) id <ClickListener> delegate;
@property (nonatomic, retain) Dictionary* dictionary;
@property (nonatomic, retain) UITableView* tableViewOutlet;
@property (nonatomic, retain) UIToolbar* toolbarOutlet;

- (UITableViewCell*) composeViewForSelectedRow: (NSIndexPath*) indexPath cellContentFrame: (CGRect) frame;
- (void) setAccessoryType: (UITableViewCellAccessoryType) type;
- (void) parseDataString: (NSString*) dataString;
@end