
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>
#import "MWFeedParser.h"

@interface RSSViewController : UITableViewController <MWFeedParserDelegate> {
	
	// Parsing
	MWFeedParser *feedParser;
	NSMutableArray *parsedItems;
	
	// Displaying
	NSArray *itemsToDisplay;
	NSDateFormatter *formatter;
	
}

// Properties
@property (nonatomic, retain) NSArray *itemsToDisplay;

- (void) moveDownView: (UIView*) view byPixels: (NSNumber*) pixels;

@end
