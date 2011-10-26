#import "ResultDetailVC.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequest.h"
#import "CXMLElement.h"
#import "ChooseCvVC.h"
#import "NoCvUploadedVC.h"
#import "ASIFormDataRequest.h"

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
	[req setRequestMethod:@"POST"];
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
	NSString* detailHTML = [detailElem stringValue];
	detailHTML = [NSString stringWithFormat:@"<html><head><style>body { font-family: 'Helvetica'; }</style></head><body>%@</body></html>", detailHTML];
	[self.descriptionTxt loadHTMLString:detailHTML baseURL: [NSURL URLWithString:@"http://fake.net"]];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
	self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"Apply" style:UIBarStyleDefault target:self action:@selector(applyAction:)];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	NSString* urlStr = [NSString stringWithFormat:@"http://jobstelecom.com/development/wsapi/mobile/retrievejob"];
	ASIFormDataRequest* req = [ASIFormDataRequest requestWithURL:[NSURL URLWithString:urlStr]];
	req.delegate = self;
	[req addPostValue:jobId forKey:@"listing_id"];
	[req setRequestMethod:@"POST"];
	[req startAsynchronous];
	for (id subview in descriptionTxt.subviews) {
		if ([[subview class] isSubclassOfClass: [UIScrollView class]]) {
			((UIScrollView *)subview).bounces = NO;
		}
	}
	self.title = @"job detail";
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
