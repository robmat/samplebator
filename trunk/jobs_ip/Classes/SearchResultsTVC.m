#import "SearchResultsTVC.h"
#import "SearchResultsTC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "LoginVC.h"
#import "ResultDetailVC.h"

@implementation SearchResultsTVC

@synthesize navCntrl, doc;

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 94;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    NSArray* nodes = [doc nodesForXPath:@"/AdvancedSearch/Job" error:nil];
	NSLog(@"%@", [doc description]);
	return [nodes count];
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
    CXMLElement* node = [[doc nodesForXPath:@"/AdvancedSearch/Job" error:nil] objectAtIndex:indexPath.row];
	CXMLNode* jobTit = [[node elementsForName:@"JobTitle"] objectAtIndex:0];
	CXMLNode* salary = [[node elementsForName:@"Salary"] objectAtIndex:0];
	CXMLNode* curren = [[node elementsForName:@"SalaryCurrencyCode"] objectAtIndex:0];
	CXMLNode* salTyp = [[node elementsForName:@"SalaryType"] objectAtIndex:0];
	CXMLNode* descri = [[node elementsForName:@"JobSummary"] objectAtIndex:0];
	cell.titleLbl.text = [jobTit stringValue];
	cell.salaryLbl.text = [NSString stringWithFormat:@"%@ %@ %@", [salary stringValue], [curren stringValue], [salTyp stringValue]];
	cell.descLbl.text = [descri stringValue];
	cell.redSignImage.hidden = YES;
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {

}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	NSString* loginUrlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/amiloggedin"];
	NSURL* url = [NSURL URLWithString:loginUrlStr];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:url];
	[req setRequestMethod:@"POST"];
	[req setDelegate:self];
	[req startAsynchronous];
	
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* xmlDoc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	CXMLElement* loggedIn = [[xmlDoc nodesForXPath:@"/AmILoggedIn/LoggedIn" error:nil] objectAtIndex:0];
	ResultDetailVC* rdvc = [[ResultDetailVC alloc] init];
	CXMLElement* jobIdElem = [[doc nodesForXPath:@"/AdvancedSearch/Job/JobSID" error:nil] objectAtIndex:lastSelectedRow];
	rdvc.jobId = [jobIdElem stringValue];
	if ([@"true" isEqualToString:[loggedIn stringValue]]) {
		[self.navCntrl pushViewController:rdvc animated:YES];
	} else {
		LoginVC* lvc = [[LoginVC alloc] init];
		lvc.viewController = rdvc;
		[self.navCntrl pushViewController:lvc animated:YES];
		[lvc release];
	}
	[rdvc release];
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)dealloc {
    [super dealloc];
	[navCntrl release];
	[doc release];
}

@end

