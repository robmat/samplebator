#import "SharepointListVC.h"
#import "SharepointListTVC.h"

@implementation SharepointListVC

@synthesize tableView, sltvc, listsData, myListName, titleStr, currentFolder;

- (void)viewDidLoad {
    [super viewDidLoad];
	sltvc = [[SharepointListTVC alloc] initWithStyle:UITableViewStylePlain];
	sltvc.listsData = listsData;
	sltvc.tableView = tableView;
	sltvc.navCntrl = self.navigationController;
	sltvc.myListName = myListName;
	sltvc.delegate = self;
	sltvc.currentFolder = currentFolder;
	[sltvc viewDidLoad];
	self.tableView.backgroundColor = [UIColor clearColor];
	backBtn.hidden = YES;
	dateFrmt = [[NSDateFormatter alloc] init];
	[dateFrmt setDateFormat:@"yyyy-MM-dd' 'HH:mm:ss"];
	tempTitle = self.title;
    [self setUpTabBarButtons];
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
    return YES;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self.tableView reloadData];
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	tempTitle = self.title;
	self.title = @"Back";
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.title = tempTitle;
}
- (IBAction)sortTypeAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[NSURL URLWithString:a1] pathExtension];
		NSString* p2 = [[NSURL URLWithString:a2] pathExtension];
		return [p1 compare: p2];
	}];
	[self.tableView reloadData];
}
- (IBAction)sortTitleAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		return [a1 compare: a2];
	}];
	[self.tableView reloadData]; 
}
- (IBAction)sortCreatedAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[listsData objectForKey:a1] objectForKey:@"ows_Created"];
		NSString* p2 = [[listsData objectForKey:a2] objectForKey:@"ows_Created"];
		NSDate* d1 = [dateFrmt dateFromString:p1];
		NSDate* d2 = [dateFrmt dateFromString:p2];
		return [d1 compare: d2];
	}];
	[self.tableView reloadData];
}
- (IBAction)sortModifiedAction: (id) sender {
	sltvc.keysArr = [sltvc.keysArr sortedArrayUsingComparator:(NSComparator)^(id a1, id a2){
		NSString* p1 = [[listsData objectForKey:a1] objectForKey:@"ows_Modified"];
		NSString* p2 = [[listsData objectForKey:a2] objectForKey:@"ows_Modified"];
		NSDate* d1 = [dateFrmt dateFromString:p1];
		NSDate* d2 = [dateFrmt dateFromString:p2];
		return [d1 compare: d2];
	}];
	[self.tableView reloadData];
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[sltvc release];
	[listsData release];
	[dateFrmt release];
	[myListName release];
	[titleStr release];
	[currentFolder release];
	[tempTitle release];
}

@end
