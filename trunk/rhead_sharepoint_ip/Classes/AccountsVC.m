#import "AccountsVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "LoginVC.h"

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
	[self.tableView reloadData];
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[accounts release];
}
- (UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	AccountTVC* cell = nil;
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"AccountTVC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (AccountTVC*) currentObject;
                break;
            }
        }
    }
	cell.delegate = self;
	cell.titleLbl.text = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"domain"];
	return cell;
}
-(NSInteger) tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [accounts count];
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 72;
}
- (void)tableView:(UITableView *)tableView_ didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView_ cellForRowAtIndexPath:indexPath] setSelected:NO];
}
@end

@implementation AccountTVC

@synthesize titleLbl, delegate;

- (void)delAction: (id) sender {
	UIActionSheet* as = [[UIActionSheet alloc] initWithTitle:@"Confirm delete" delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:@"Delete" otherButtonTitles:nil];
	[as showInView: delegate.view];
	[as release];
}
- (void)goAction: (id) sender {
	NSMutableDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	NSDictionary* loginDict = [accountsDict objectForKey:titleLbl.text];
	LoginVC* loginVC = [[LoginVC alloc] init];
	[self.delegate.navigationController pushViewController:loginVC animated:YES];
	loginVC.loginTxt.text = [loginDict objectForKey:@"login"];
	loginVC.passwTxt.text = [loginDict objectForKey:@"password"];
	loginVC.domainTxt.text = [loginDict objectForKey:@"domain"];
	[loginVC release];
	
}
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 0) {	
		NSMutableDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
		[accountsDict removeObjectForKey:titleLbl.text];
		[accountsDict writeToFile:[rhead_sharepoint_ipAppDelegate accountsPath] atomically:YES];
		[delegate viewDidLoad];
	}
}
- (void)dealloc {
	[super dealloc];
	[titleLbl release];
	[delegate release];
}

@end
