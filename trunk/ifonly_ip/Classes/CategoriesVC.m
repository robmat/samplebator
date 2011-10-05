#import "CategoriesVC.h"
#import "ifonly_ipAppDelegate.h"
#import "LatestVideosByCategoryVC.h"

@implementation CategoriesVC

@synthesize houseCount, gardenCount, toolsCount, personalCount, electricalCount, miscCount, ytService;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.ytService = [ifonly_ipAppDelegate getYTServiceWithcredentials:NO];
	NSString* accountName = [[NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"account" ofType:@"plist"]] objectForKey:@"ytAccountName"];
	NSURL* url = [NSURL URLWithString:[NSString stringWithFormat:@"http://gdata.youtube.com/feeds/api/users/%@/uploads", accountName]];
	[ytService fetchFeedWithURL:url delegate:self didFinishSelector:@selector(entryListFetchTicket:finishedWithFeed:error:)];
	self.title = @"if Only video archive";
	backBtn.hidden = YES;
}
- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error {
	int houseCountInt = 0;
	int gardenCounttInt = 0;
	int toolsCountInt = 0;
	int personalCountInt = 0;
	int electricalCountInt = 0;
	int miscCountInt = 0;
	for (int i = 0; i < [[feed entries] count]; i++) {
        GDataEntryBase* entry = [[feed entries] objectAtIndex:i];
		NSString* title = [[entry title] stringValue];
		if ([title rangeOfString:@"Household Products"].location != NSNotFound) {
			houseCountInt++;
		}
		if ([title rangeOfString:@"Garden Products"].location != NSNotFound) {
			gardenCounttInt++;
		}
		if ([title rangeOfString:@"Tools/Machinery"].location != NSNotFound) {
			toolsCountInt++;
		}
		if ([title rangeOfString:@"Electrical Goods"].location != NSNotFound) {
			electricalCountInt++;
		}
		if ([title rangeOfString:@"Personal Products"].location != NSNotFound) {
			personalCountInt++;
		}
		if ([title rangeOfString:@"Miscellaneous"].location != NSNotFound) {
			miscCountInt++;
		}
		houseCount.text = [NSString stringWithFormat:@"(%d)", houseCountInt];
		gardenCount.text = [NSString stringWithFormat:@"(%d)", gardenCounttInt];
		electricalCount.text = [NSString stringWithFormat:@"(%d)", electricalCountInt];
		toolsCount.text = [NSString stringWithFormat:@"(%d)", toolsCountInt];
		personalCount.text = [NSString stringWithFormat:@"(%d)", personalCountInt];
		miscCount.text = [NSString stringWithFormat:@"(%d)", miscCountInt];
    }
}
- (void)viewWillAppear: (BOOL) animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (IBAction) householdAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Household Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) gardenToolsAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Garden Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) electricalAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Electrical Goods";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) toolsAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Tools/Machinery";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) personalAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Personal Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) miscAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Miscelaneous";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (void)dealloc {
    [super dealloc];
	[houseCount release];
	[gardenCount release];
	[toolsCount release];
	[personalCount release];
	[electricalCount release];
	[miscCount release];
	[ytService release];
}

@end
