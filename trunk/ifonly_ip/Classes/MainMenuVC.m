#import "MainMenuVC.h"
#import "ChooseVideoSourceVC.h"
#import "LatestVideosByCategoryVC.h"
#import "ifonly_ipAppDelegate.h"
#import "CompetitorsVC.h"

@implementation MainMenuVC

@synthesize ytService;

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
}
- (void) viewWillDisappear:(BOOL)animated {
	self.title = @"Back";
}
- (IBAction) recordMovieAction: (id) sender {
	ChooseVideoSourceVC* cvvc = [[ChooseVideoSourceVC alloc] init];
	[self.navigationController pushViewController:cvvc animated:YES];
	[cvvc release];
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
- (IBAction) demoAction: (id) sender {
	[self playPlak];
	self.ytService = [ifonly_ipAppDelegate getYTServiceWithcredentials:YES];
	NSString* accountName = [[NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"account" ofType:@"plist"]] objectForKey:@"ytAccountName"];
	NSURL* url = [NSURL URLWithString:[NSString stringWithFormat:@"http://gdata.youtube.com/feeds/api/users/%@/uploads?q=filming_tutorial_video", accountName]];
	[ytService fetchFeedWithURL:url
					   delegate:self
			  didFinishSelector:@selector(entryListFetchTicket:finishedWithFeed:error:)];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
}
- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	NSLog(@"%@", [feed description]);
	if ([[feed entries] count] > 0) {
		GDataEntryBase* entry = [[feed entries] objectAtIndex:0];
		[[UIApplication sharedApplication] openURL:[NSURL URLWithString:[[entry content] sourceURI]]];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:@"No tutorial movie found." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
}
- (IBAction) competitionAction: (id) sender {
	CompetitorsVC* cvc = [[CompetitorsVC alloc] init];
	[self.navigationController pushViewController:cvc animated:YES];
	[cvc release];
}
- (IBAction) allVideosAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = nil;
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc setTitle:@"All videos"];
	[lvbcvc release];
}
- (void)viewWillAppear: (BOOL) animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = YES;
	self.title = @"Main menu";
}
- (void)dealloc {
    [super dealloc];
	[ytService release];
}

@end
