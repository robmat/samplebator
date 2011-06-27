
//Copyright Applicable Ltd 2011

#import "RSSViewController.h"
#import "NSString+HTML.h"
#import "MWFeedParser.h"
#import "DetailTableViewController.h"
#import "PathwayCell.h"

@implementation RSSViewController

@synthesize itemsToDisplay;

- (void)viewDidLoad {
	[super viewDidLoad];
	self.title = @"Loading...";
	formatter = [[NSDateFormatter alloc] init];
	[formatter setDateStyle:NSDateFormatterShortStyle];
	[formatter setTimeStyle:NSDateFormatterShortStyle];
	parsedItems = [[NSMutableArray alloc] init];
	self.itemsToDisplay = [NSArray array];
	self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemRefresh 
																							target:self 
																							action:@selector(refresh)] autorelease];
	NSURL *feedURL = [NSURL URLWithString:@"http://www.healtheastcic.co.uk/RSS/MembersLatestNews.rss"];
	feedParser = [[MWFeedParser alloc] initWithFeedURL:feedURL];
	feedParser.delegate = self;
	feedParser.feedParseType = ParseTypeFull; // Parse feed info and all items
	feedParser.connectionType = ConnectionTypeAsynchronously;
	[feedParser parse];

}

- (void)refresh {
	self.title = @"Refreshing...";
	[parsedItems removeAllObjects];
	[feedParser stopParsing];
	[feedParser parse];
	self.tableView.userInteractionEnabled = NO;
	self.tableView.alpha = 0.3;
}

- (void)feedParserDidStart:(MWFeedParser *)parser {
	NSLog(@"Started Parsing: %@", parser.url);
}

- (void)feedParser:(MWFeedParser *)parser didParseFeedInfo:(MWFeedInfo *)info {
	NSLog(@"Parsed Feed Info: “%@”", info.title);
	self.title = @"News";//info.title;
}

- (void)feedParser:(MWFeedParser *)parser didParseFeedItem:(MWFeedItem *)item {
	NSLog(@"Parsed Feed Item: “%@”", item.title);
	if (item) [parsedItems addObject:item];	
}

- (void)feedParserDidFinish:(MWFeedParser *)parser {
	NSLog(@"Finished Parsing%@", (parser.stopped ? @" (Stopped)" : @""));
	self.itemsToDisplay = [parsedItems sortedArrayUsingDescriptors:
						   [NSArray arrayWithObject:[[[NSSortDescriptor alloc] initWithKey:@"date" 
																				 ascending:NO] autorelease]]];
	self.tableView.userInteractionEnabled = YES;
	self.tableView.alpha = 1;
	[self.tableView reloadData];
}

- (void)feedParser:(MWFeedParser *)parser didFailWithError:(NSError *)error {
	NSLog(@"Finished Parsing With Error: %@", error);
	self.title = @"Failed";
	self.itemsToDisplay = [NSArray array];
	[parsedItems removeAllObjects];
	self.tableView.userInteractionEnabled = YES;
	self.tableView.alpha = 1;
	[self.tableView reloadData];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return itemsToDisplay.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	PathwayCell *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"PathwayCell" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (PathwayCell *) currentObject;
                break;
            }
        }
    }
	cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	MWFeedItem *item = [itemsToDisplay objectAtIndex:indexPath.row];
	NSMutableDictionary* dataDict = [NSMutableDictionary dictionaryWithCapacity:2];
	if (item) {
		NSString *itemTitle = item.title ? [item.title stringByConvertingHTMLToPlainText] : @"[No Title]";
		NSString *itemSummary = item.summary ? [item.summary stringByConvertingHTMLToPlainText] : @"[No Summary]";
		[dataDict setObject:itemTitle forKey:@"Title"];
		NSMutableString *subtitle = [NSMutableString string];
		if (item.date) [subtitle appendFormat:@"%@: ", [formatter stringFromDate:item.date]];
		[subtitle appendString:itemSummary];
		[dataDict setObject:subtitle forKey:@"Text"];
		[self moveDownView:cell.label byPixels:[NSNumber numberWithInt:10]];
		[self moveDownView:cell.detailLabel byPixels:[NSNumber numberWithInt:10]];
		[self moveLeftView:cell.label byPixels:[NSNumber numberWithInt:20]];
		[self moveLeftView:cell.detailLabel byPixels:[NSNumber numberWithInt:20]];
	}
	cell.data = dataDict;
	[cell initializeCell];
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	DetailTableViewController *detail = [[DetailTableViewController alloc] initWithStyle:UITableViewStyleGrouped];
	detail.item = (MWFeedItem *)[itemsToDisplay objectAtIndex:indexPath.row];
	[self.navigationController pushViewController:detail animated:YES];
	[detail release];
	[self.tableView deselectRowAtIndexPath:indexPath animated:YES];
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 88.0;
}
- (void) moveDownView: (UIView*) view byPixels: (NSNumber*) pixels {
	CGRect frame = view.frame;
	frame = CGRectMake(frame.origin.x, frame.origin.y + [pixels floatValue], frame.size.width, frame.size.height);
	view.frame = frame;
}
- (void) moveLeftView: (UIView*) view byPixels: (NSNumber*) pixels {
	CGRect frame = view.frame;
	frame = CGRectMake(frame.origin.x - [pixels floatValue], frame.origin.y, frame.size.width + [pixels floatValue], frame.size.height);
	view.frame = frame;
}
- (void)dealloc {
	[formatter release];
	[parsedItems release];
	[itemsToDisplay release];
	[feedParser release];
    [super dealloc];
}

@end
