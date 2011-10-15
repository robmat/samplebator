#import "MyFavJobsVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "CXMLElement.h"
#import "CXMLNode.h"
#import "SearchResultsTC.h"
#import "ResultDetailVC.h"

@implementation MyFavJobsVC

@synthesize doc, tableView, deleteFavJobId;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (IBAction) editAction: (id) sender {
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	if ([[[request url] lastPathComponent] isEqualToString:@"listfavourites"]) {
		[doc release];
		doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
		[tableView reloadData];
	}
	if ([[[request url] lastPathComponent] isEqualToString:@"deletefavourite"]) {
		[self viewDidLoad];
	}
}	
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [[doc nodesForXPath:@"/Favourites/Favourite" error:nil] count];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView_ {
	return 1;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	SearchResultsTC *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SearchResultsTC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (SearchResultsTC *) currentObject;
                break;
            }
        }
    }
    CXMLElement* node = [[doc nodesForXPath:@"/Favourites/Favourite" error:nil] objectAtIndex:indexPath.row];
	CXMLNode* jobTit = [[node elementsForName:@"JobTitle"] objectAtIndex:0];
	CXMLNode* salary = [[node elementsForName:@"Salary"] objectAtIndex:0];
	CXMLNode* curren = [[node elementsForName:@"SalaryCurrencyCode"] objectAtIndex:0];
	CXMLNode* salTyp = [[node elementsForName:@"SalaryType"] objectAtIndex:0];
	CXMLNode* descri = [[node elementsForName:@"Summary"] objectAtIndex:0];
	CXMLNode* JobSID = [[node elementsForName:@"JobSID"] objectAtIndex:0];
	cell.titleLbl.text = [jobTit stringValue];
	cell.salaryLbl.text = [NSString stringWithFormat:@"%@ %@ %@", [salary stringValue], [curren stringValue], [salTyp stringValue]];
	cell.descLbl.text = [descri stringValue];
	cell.delegate = self;
	cell.jobId = [JobSID stringValue];
	return cell;
}
- (void) tableView:(UITableView *)tableView didDeselectRowAtIndexPath:(NSIndexPath *)indexPath {
	ResultDetailVC* rdvc = [[ResultDetailVC alloc] init];
	CXMLElement* jobIdElem = [[doc nodesForXPath:@"/Favourites/Favourite/JobSID" error:nil] objectAtIndex:indexPath.row];
	rdvc.jobId = [jobIdElem stringValue];
	[self.navigationController pushViewController:rdvc animated:YES];
	[rdvc release];
}
- (void) redButtonAction: (NSString*) jobId {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Confirm" 
													message:@"Remove from Fav jobs?" 
												   delegate:self
										  cancelButtonTitle:@"Cancel" otherButtonTitles:@"Delete", nil];
	[alert show];
	[alert release];
	self.deleteFavJobId = jobId;
}
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 1) {
		NSString* urlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/deletefavourite?JobSID=%@", deleteFavJobId];
		ASIHTTPRequest* request = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:urlStr]];
		request.delegate = self;
		[request startAsynchronous];
	}
}
- (void)viewDidLoad {
    [super viewDidLoad];
	ASIHTTPRequest* request = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listfavourites"]];
	request.delegate = self;
	[request startAsynchronous];
	backBtn.hidden = YES;
}
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 94;
}
- (void)dealloc {
    [super dealloc];
	[doc release];
	[tableView release];
	[deleteFavJobId release];
}

@end
