
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>
#import "MWFeedItem.h"

@interface DetailTableViewController : UITableViewController {
	MWFeedItem *item;
	NSString *dateString, *summaryString;
}

@property (nonatomic, retain) MWFeedItem *item;
@property (nonatomic, retain) NSString *dateString, *summaryString;

@end
