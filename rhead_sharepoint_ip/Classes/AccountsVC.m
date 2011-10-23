#import "AccountsVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation AccountsVC

@synthesize accounts,tableView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {

    }
    return self;
}
- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	self.accounts = [accountsDict allValues];
	self.title = @"Projects";
	self.tableView.backgroundColor = [UIColor colorWithRed:0.195 green:0.234 blue:0.437 alpha:1];
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	self.navigationController.navigationBarHidden = YES;
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[accounts release];
}
- (UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	AccountTVC* cell = nil;
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"AccountsTVC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (AccountTVC*) currentObject;
                break;
            }
        }
    }
	cell.titleLbl.text = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"domain"];
	return cell;
}
-(NSInteger) tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [accounts count];
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 72;
}
@end

@implementation AccountTVC

@synthesize titleLbl;

- (void)delAction: (id) sender {
	
}
- (void)goAction: (id) sender {
	
}
- (void)dealloc {
	[super dealloc];
	[titleLbl release];
}

@end
