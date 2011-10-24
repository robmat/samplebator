#import "SearchResultsVC.h"
#import "SearchResultsTVC.h"
#import "RefineVC.h"

@implementation SearchResultsVC

@synthesize doc, tableVew, location, keyword;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
		
    }
    return self;
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	self.navigationController.navigationBarHidden = YES;
}
- (void)saveSearchAction: (id) sender {
	
}
- (void)refineAction: (id) sender {
	RefineVC* rvc = [[RefineVC alloc] init];
	[self.navigationController pushViewController:rvc animated:YES];
	rvc.locationSearchBar.text = location;
	rvc.keywordSearchBar.text = keyword;
	rvc.doc = doc;
	[rvc release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	tableViewController = [[SearchResultsTVC alloc] initWithStyle:UITableViewStylePlain];
	tableViewController.tableView = tableVew;
	tableViewController.doc = doc;
	tableViewController.navCntrl = self.navigationController;
	[tableViewController viewDidLoad];
	self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"Refine" style:UIBarStyleDefault target:self action:@selector(refineAction:)];
	UIButton* saveSearchBtn = [UIButton buttonWithType:UIButtonTypeCustom];
	[saveSearchBtn setImage:[UIImage imageNamed:@"save_search_btn.png"] forState:UIControlStateNormal];
	[saveSearchBtn addTarget:self action:@selector(saveSearchAction:) forControlEvents:UIControlEventTouchUpInside];
	self.navigationItem.titleView = saveSearchBtn;
	[saveSearchBtn sizeToFit];
}
- (void)dealloc {
    [super dealloc];
	[doc release];
	[tableVew release];
	[keyword release];
	[location release];
}

@end
