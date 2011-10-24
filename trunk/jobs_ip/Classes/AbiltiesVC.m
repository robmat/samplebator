#import "AbiltiesVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "LoginVC.h"
#import "SavedSearchVC.h"
#import "MyCVs.h"
#import "MyFavJobsVC.h"

@implementation AbiltiesVC

@synthesize tableView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	atvc = [[AbiltiesTVC alloc] initWithStyle:UITableViewStyleGrouped];
	atvc->loggedIn = loggedIn;
	atvc->uploadedCV = uploadedCV;
	atvc->recheckStatusFlag = NO;
	atvc.tableView = tableView;
	atvc.navCntrl = self.navigationController;
	[atvc viewDidLoad];
	self.tableView.backgroundColor = [UIColor clearColor];
}

- (void)viewWillAppear:(BOOL)animated {
	if (atvc->recheckStatusFlag) {
		[atvc recheckStatus];
	}
	self.navigationController.navigationBarHidden = YES;
}

- (void)dealloc {
    [super dealloc];
	[tableView release];
	[atvc release];
}

@end

@implementation AbiltiesTVC

@synthesize navCntrl;

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 42;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return 7;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle reuseIdentifier:CellIdentifier] autorelease];
	}
	cell.accessoryView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"green_disclousure.png"]];
	cell.tag = 1;
	switch (indexPath.row) {
		case 0: cell.textLabel.text = @"Search for jobs"; break;
		case 1: cell.textLabel.text = @"Save searched & add alerts"; break;
		case 2: cell.textLabel.text = @"Keep a list of Fav. jobs"; break;
		case 3: cell.textLabel.text = @"Keep a list of Fav. recruiters"; break;
		case 4: cell.textLabel.text = @"Apply for a job"; break;
		case 5: cell.textLabel.text = @"Track recent job application"; break;
		case 6: cell.textLabel.text = @"Manage your CV's"; break;
	}
	cell.detailTextLabel.text = @"You can do this now";
	if (indexPath.row > 0 && !loggedIn) {
		cell.detailTextLabel.text = @"You will need to login to do this";
		cell.accessoryView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"red_disclousure.png"]];
		cell.tag = 0;
	}
	if (indexPath.row > 3 && !uploadedCV && !loggedIn) {
		cell.detailTextLabel.text = @"You will need to login and upload a CV to do this";
		cell.accessoryView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"red_disclousure.png"]];
		cell.tag = 0;
	}
	if (indexPath.row > 3 && !uploadedCV && loggedIn) {
		cell.detailTextLabel.text = @"You will need to upload a CV to do this";
		cell.accessoryView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"red_disclousure.png"]];
		cell.tag = 0;
	}
	cell.textLabel.font = [UIFont fontWithName:@"Helvetica" size:16];
    return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {

}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	UITableViewCell* cell = [tableView cellForRowAtIndexPath:indexPath];
	if (cell.tag == 1) {
		if ([cell.textLabel.text isEqualToString:@"Search for jobs"]) {
			SavedSearchVC* ssvc = [[SavedSearchVC alloc] init];
			[self.navCntrl pushViewController:ssvc animated:YES];
			[ssvc release];
		}
		if ([cell.textLabel.text isEqualToString:@"Manage your CV's"]) {
			MyCVs* ssvc = [[MyCVs alloc] init];
			[self.navCntrl pushViewController:ssvc animated:YES];
			[ssvc release];
		}
		if ([cell.textLabel.text isEqualToString:@"Keep a list of Fav. jobs"]) {
			MyFavJobsVC* mfjvc = [[MyFavJobsVC alloc] init];
			[self.navCntrl pushViewController:mfjvc animated:YES];
			[mfjvc release];
		}	
		//todo rest shit
	} else {
		recheckStatusFlag = YES;
		LoginVC* lcv = [[LoginVC alloc] init];
		[self.navCntrl pushViewController:lcv animated:YES];
		[lcv release];
	}
}

- (void)recheckStatus {
	if (!recheckStatusFlag) return;
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/amiloggedin"]];
	[req setRequestMethod:@"POST"];
	[req startSynchronous];
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[req responseData] options:0 error:nil];
	loggedIn = [[[[doc nodesForXPath:@"/AmILoggedIn/LoggedIn" error:nil] objectAtIndex:0] stringValue] isEqualToString:@"true"];
	[doc release];
	
	req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	[req setRequestMethod:@"POST"];
	[req startSynchronous];
	doc = [[CXMLDocument alloc] initWithData:[req responseData] options:0 error:nil];
	uploadedCV = [[doc nodesForXPath:@"/CVList/CV" error:nil] count] > 0;
	[doc release];
	[self.tableView reloadData];
}
- (void)dealloc {
    [super dealloc];
	[navCntrl release];
}

@end