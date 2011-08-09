#import "LatestVideosByCategoryVC.h"
#import "ifonly_ipAppDelegate.h"
#import "LatestVideosByCategoryTC.h"

@implementation LatestVideosByCategoryVC

@synthesize category, ytService, tableView, tableVC;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = category;
	self.ytService = [ifonly_ipAppDelegate getYTService];
	NSURL* url = [NSURL URLWithString:@"http://gdata.youtube.com/feeds/api/users/robmat666/uploads"];
	[ytService fetchFeedWithURL:url
					   delegate:self
			  didFinishSelector:@selector(entryListFetchTicket:finishedWithFeed:error:)];
	self.tableVC = [[LatestVideosByCategoryTC alloc] init];
	self.tableVC.tableView = tableView;
	[self.tableVC viewDidLoad];
}
- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error {
	NSMutableArray* results = [NSMutableArray arrayWithCapacity:999];
	for (int i = 0; i < [[feed entries] count]; i++) {
        GDataEntryBase *entry = [[feed entries] objectAtIndex:i];
		if ( !([[[entry title] stringValue] rangeOfString:category].location == NSNotFound) ) {
			[results addObject:entry];
		}
        /*
		NSLog(@"Title: %@", [[entry title] stringValue]);
		NSLog(@"Id: %@", [[entry identifier] description]);
		NSLog(@"PD: %@", [[entry publishedDate] description]);
		NSLog(@"UD: %@", [[entry updatedDate] description]);
		NSLog(@"ED: %@", [[entry editedDate] description]);
		NSLog(@"ET: %@", [[entry ETag] description]);
		NSLog(@"Kind: %@", [[entry kind] description]);
		NSLog(@"Resource id: %@", [[entry resourceID] description]);
		NSLog(@"Summary: %@", [[entry summary] stringValue]);
		NSLog(@"Content: %@", [[entry content] description]);
		NSLog(@"Title: %@", [[entry rightsString] stringValue]);
		NSLog(@"Title: %@", [[entry title] stringValue]);
		NSLog(@"Title: %@", [[entry title] stringValue]);
		NSLog(@"Title: %@", [[entry title] stringValue]);
		*/
    }
	self.tableVC.dataArr = results;
	[self.tableVC.tableView reloadData];
}
- (void)dealloc {
	[tableVC release];
	[tableView release];
	[ytService release];
	[category release];
    [super dealloc];
}

@end
