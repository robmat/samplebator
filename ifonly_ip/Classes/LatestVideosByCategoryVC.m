#import "LatestVideosByCategoryVC.h"
#import "ifonly_ipAppDelegate.h"
#import "LatestVideosByCategoryTC.h"

@implementation LatestVideosByCategoryVC

@synthesize category, ytService, tableView, tableVC, orderBy, searchBar, actIndView, searchBtn, noVidsLbl, catImgView;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = category;
	self.catImgView = [[UIImageView alloc] initWithFrame: CGRectMake(277, 4, 35, 35)];
	[self.navigationController.navigationBar addSubview:catImgView];
	self.ytService = [ifonly_ipAppDelegate getYTServiceWithcredentials:NO];
	NSString* accountName = [[NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"account" ofType:@"plist"]] objectForKey:@"ytAccountName"];
	NSString* catParam = [category stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]; 
	NSURL* url = [NSURL URLWithString:[NSString stringWithFormat:@"http://gdata.youtube.com/feeds/api/users/%@/uploads?q=%@", accountName, catParam == nil ? @"" : catParam]];
	NSLog(@"%@", [url description]);
	[ytService fetchFeedWithURL:url
					   delegate:self
			  didFinishSelector:@selector(entryListFetchTicket:finishedWithFeed:error:)];
	self.tableVC = [[LatestVideosByCategoryTC alloc] init];
	self.tableVC.tableView = tableView;
	self.tableVC.navCntrl = self.navigationController;
	[self.tableVC viewDidLoad];
	NSLog(@"Launched %@ with category: %@", [self description], category);
	[orderBy addTarget:self	action:@selector(sortAction:) forControlEvents:UIControlEventValueChanged];
	[actIndView startAnimating];
	backBtn.hidden = YES;
	self.navigationController.navigationBarHidden = NO;
	NSString* imgName = nil;
	if ([category isEqualToString:@"Household Products"]) {
		imgName = @"white_household.png";
	}
	if ([category isEqualToString:@"Garden Products"]) {
		imgName = @"white_garden.png";
	}
	if ([category isEqualToString:@"Tools/Machinery"]) {
		imgName = @"white_machinery.png";
	}
	if ([category isEqualToString:@"Electrical Goods"]) {
		imgName = @"white_electrical.png";
	}
	if ([category isEqualToString:@"Personal Products"]) {
		imgName = @"white_personal.png";
	}
	if ([category isEqualToString:@"Miscellaneous"]) {
		imgName = @"white_misc.png";
	}
	if (imgName != nil) {
		UIImage* img = [UIImage imageNamed:imgName];
		[catImgView setImage:img];
	} else {
		[catImgView setImage:nil];
	}
}
- (IBAction)searchAction {
	[super animateView:searchBar up:NO distance:88];
	searchBtn.enabled = NO;
	backBtn.hidden = YES;
}
- (void)searchBar:(UISearchBar *)searchBar textDidChange:(NSString *)searchText {
	searchText = [searchText lowercaseString];
	NSMutableArray* newArray = [NSMutableArray arrayWithCapacity: [self.tableVC.dataArr count]];
	for (GDataEntryYouTubeVideo* entry in self.tableVC.originalDataArr) {
		NSString* title = [[[entry title] stringValue] lowercaseString];
		if ([title rangeOfString:searchText].location != NSNotFound) {
			[newArray addObject:entry];
		}
	}
	self.tableVC.dataArr = newArray;
	[self.tableVC.tableView reloadData];
}
- (void)searchBarCancelButtonClicked:(UISearchBar *)searchBar_ {
	[super animateView:searchBar up:YES distance:88];
	[searchBar resignFirstResponder];
	self.tableVC.dataArr = [NSArray arrayWithArray:self.tableVC.originalDataArr];
	[self.tableVC.tableView reloadData];
	searchBtn.enabled = NO;
}
- (void)sortAction: (id) sender {
	UISegmentedControl* segment = (UISegmentedControl*) sender;
	int sel = segment.selectedSegmentIndex;
	if (sel == 0) {
		self.tableVC.dataArr = [self.tableVC.dataArr sortedArrayUsingComparator:^(id obj1, id obj2) {
			GDataEntryYouTubeVideo* entry1 = (GDataEntryYouTubeVideo*) obj1;
			GDataEntryYouTubeVideo* entry2 = (GDataEntryYouTubeVideo*) obj2;
			NSDate* date1 = [[entry1 publishedDate] date];
			NSDate* date2 = [[entry2 publishedDate] date];
			return (NSComparisonResult)[date2 compare:date1];
		}];
		[self.tableVC.tableView reloadData];
	}
	if (sel == 1) {
		self.tableVC.dataArr = [self.tableVC.dataArr sortedArrayUsingComparator:^(id obj1, id obj2) {
			GDataEntryYouTubeVideo* entry1 = (GDataEntryYouTubeVideo*) obj1;
			GDataEntryYouTubeVideo* entry2 = (GDataEntryYouTubeVideo*) obj2;
			NSNumber* views1 = [[entry1 statistics] viewCount];
			NSNumber* views2 = [[entry2 statistics] viewCount];
			if (views1 == nil) {
				views1 = [NSNumber numberWithInt:0];
			}
			if (views2 == nil) {
				views2 = [NSNumber numberWithInt:0];
			}
			return (NSComparisonResult)[views2 compare:views1];
		}];
		[self.tableVC.tableView reloadData];
	}
}
- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error {
	NSMutableArray* results = [NSMutableArray arrayWithCapacity:999];
	for (int i = 0; i < [[feed entries] count]; i++) {
        GDataEntryBase* entry = [[feed entries] objectAtIndex:i];
		NSLog(@"Movie title: %@", [[entry title] stringValue]);
		if (category == nil || !([[[entry title] stringValue] rangeOfString:category].location == NSNotFound)) {
			if ([[[entry title] stringValue] rangeOfString:@"filming_tutorial_video"].location == NSNotFound) {	
				if ([[[entry title] stringValue] rangeOfString:@"filming tutorial video"].location == NSNotFound) {
					[results addObject:entry];
				}
			}
		}
    }
	self.tableVC.dataArr = results;
	self.tableVC.originalDataArr = [NSArray arrayWithArray:results];
	[self.tableVC.tableView reloadData];
	[actIndView stopAnimating];
	[actIndView setHidden:YES];
	NSLog(@"Results after filtering: %i", [results count]);
	if ([results count] == 0) {
		tableView.hidden = YES;
		noVidsLbl.hidden = NO;
	} else {
		tableView.hidden = NO;
		noVidsLbl.hidden = YES;
	}

}
- (void)viewDidAppear: (BOOL) animated {
	[super viewDidAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	self.catImgView.hidden = YES;
}
- (void)dealloc {
	[actIndView release];
	[searchBar release];
	[orderBy release];
	[tableVC release];
	[tableView release];
	[ytService release];
	[category release];
	[searchBtn release];
	[noVidsLbl release];
	[catImgView release];
    [super dealloc];
}

@end
