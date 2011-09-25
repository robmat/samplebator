#import "ResultDetailVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "CXMLElement.h"
#import "ChooseCvVC.h"
#import "NoCvUploadedVC.h"

@implementation ResultDetailVC

@synthesize jobTitleTxt, placeTxt, salaryTxt, descriptionTxt, jobId;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)applyAction: (id) sender {
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listcvs"]];
	[req startSynchronous];
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[req responseData] options:0 error:nil];
	int nodes = [[doc nodesForXPath:@"/CVList/CV" error:nil] count];
	if (nodes > 0) {
		ChooseCvVC* ccvvc = [[ChooseCvVC alloc] init];
		[self.navigationController pushViewController:ccvvc animated:YES];
		ccvvc.jobTitleLbl.text = jobTitleTxt.text;
		ccvvc.jobId = jobId;
		[ccvvc release];
	} else {
		NoCvUploadedVC* ncuvc = [[NoCvUploadedVC alloc] init];
		[self.navigationController pushViewController:ncuvc animated:YES];
		[ncuvc release];
	}
}

- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* xmlDoc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	CXMLElement* titleElem = [[xmlDoc nodesForXPath:@"/Job/JobTitle" error:nil] objectAtIndex:0];
	CXMLElement* cityElem = [[xmlDoc nodesForXPath:@"/Job/City" error:nil] objectAtIndex:0];
	CXMLElement* countryElem = [[xmlDoc nodesForXPath:@"/Job/Country" error:nil] objectAtIndex:0];
	CXMLElement* salaryElem = [[xmlDoc nodesForXPath:@"/Job/Salary" error:nil] objectAtIndex:0];
	CXMLElement* currCodeElem = [[xmlDoc nodesForXPath:@"/Job/SalaryCurrencyCode" error:nil] objectAtIndex:0];
	CXMLElement* salaryTypeElem = [[xmlDoc nodesForXPath:@"/Job/SalaryType" error:nil] objectAtIndex:0];
	CXMLElement* detailElem = [[xmlDoc nodesForXPath:@"/Job/JobDetail" error:nil] objectAtIndex:0];
	self.jobTitleTxt.text = [titleElem stringValue];
	self.placeTxt.text = [NSString stringWithFormat:@"%@ - %@", [cityElem stringValue], [countryElem stringValue]];
	self.salaryTxt.text = [NSString stringWithFormat:@"%@%@ %@", [salaryElem stringValue], [currCodeElem stringValue], [salaryTypeElem stringValue]];
	[self.descriptionTxt loadHTMLString:[detailElem stringValue] baseURL: [NSURL URLWithString:@"http://fake.net"]];
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	NSString* urlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/retrievejob?listing_id=%@", jobId];
	ASIHTTPRequest* req = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:urlStr]];
	req.delegate = self;
	[req startAsynchronous];
}

- (void)dealloc {
    [super dealloc];
	[jobTitleTxt release];
	[placeTxt release];
	[salaryTxt release];
	[descriptionTxt release];
	[jobId release];
}

@end
